<?php

namespace App\Services;

use App\DTO\Customer;
use App\DTO\Product;
use App\DTO\Remission as Transform;
use App\DTO\RemissionDetail;
use App\Models\Remission as Model;
use App\Models\RemissionDetail as ModelDetail;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Servicio que maneja todos los usuarios en el sistema
 *
 * @author Juan Cruz
 *
 * @version 1.0
 */
class RemissionService
{
    public const REMISSION_KEY = 'messages.Remission';
    public const PDF_NAME = 'Factura.pdf';

    public const ERROR_REMISSION = 'messages.An error occurred while getting the remission';

    public const ERROR_CREATING_REMISSION = 'messages.An error occurred while creating the remission';

    /**
     * Registra un remision en la base de datos
     *
     * @throws Exception
     */
    public function create(Transform $data): Transform
    {
        $result = Model::create($data->toCreate());

        foreach ($data->getDetails() as $row) {
            ModelDetail::create($row->toCreate($result->id));
        }
        $data->setId($result->id);

        return $data;
    }

    /**
     * Obtiene un remision por su id
     */
    public function findById(int $id): ?Transform
    {
        $result = Model::find($id);
        if (! is_null($result)) {
            $self = $this->transform($result);
            $self->setDetails(...$this->findDetails($id));

            return $self;
        }

        return null;
    }

    /**
     * Obtiene el ultimo consecutivo y le suma 1
     */
    public function consecutive(): string
    {
        $result = Model::orderBy('consecutive', 'desc')->first();
        if (! is_null($result)) {
            $last = $result->consecutive;
            $new = intval($last) + 1;

            return str_pad(strval($new), 4, '0', STR_PAD_LEFT);
        }

        return '0001';
    }

    /**
     * Obtiene todos las remisiones registradas en el sistema
     *
     * @return array<Transform>
     */
    public function findAll(): array
    {
        $rows = Model::orderBy('consecutive', 'desc')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->transform($row);
        }

        return $results;
    }

    /**
     * Obtiene todos las remisiones registradas en el sistema
     *
     * @param int  $id
     * @return array<RemissionDetail>
     */
    private function findDetails(int $id): array
    {
        $rows = ModelDetail::where('idremission', $id)
            ->orderBy('id', 'asc')
            ->get();

        $results = [];
        foreach ($rows as $row) {
            $results[] = $this->transformDetails($row);
        }

        return $results;
    }

    /**
     * Genera el PDF de una remision
     *
     * @param int $id
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePDF(int $id): \Barryvdh\DomPDF\PDF
    {
        $result = $this->findById($id);
        if (is_null($result)) {
            throw new Exception(__(RemissionService::ERROR_REMISSION));
        };

        $remission = $result->toArray();
        $special = $remission['customer']['special'];
        $pdf = Pdf::loadView(
            'invoice',
            compact(
                'remission',
                'special'
            )
        );

        return $pdf;
    }

    private function transform(Model $model): Transform
    {
        $customer = new Customer();
        $customer->setId($model['idcustomer']);
        $customer->setName($model['customer']['name']);
        $customer->setIdentification($model['customer']['identification']);
        $customer->setEmail($model['customer']['email']);
        $customer->setPhone($model['customer']['phone']);
        $customer->setCellPhone($model['customer']['cellphone']);
        $customer->setAddress($model['customer']['address']);
        $customer->setMinimunValue($model['customer']['minimunvalue']);
        $customer->setSpecial($model['customer']['special']);

        $self = new Transform();
        $self->setId($model['id']);
        $self->setConsecutive($model['consecutive']);
        $self->setTotal($model['total']);
        $self->setTotalPackages($model['totalpackages']);
        $self->setCustomer($customer);
        $self->setCreatedAt($model['createdat']);

        return $self;
    }

    private function transformDetails(ModelDetail $model): RemissionDetail
    {
        $product = new Product();
        $product->setId($model['idproduct']);
        $product->setName($model['product']['name']);

        $self = new RemissionDetail();
        $self->setId($model['id']);
        $self->setTotal($model['total']);
        $self->setPackages($model['packages']);
        $self->setPrice($model['price']);
        $self->setQuantity($model['quantity']);
        $self->setReference($model['reference']);
        $self->setColors($model['colors']);
        $self->setMinimum($model['minimum']);
        $self->setProduct($product);

        return $self;
    }
}
