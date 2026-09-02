<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Collection $customers;


    public function __construct(Collection $customers)
    {
        $this->customers = $customers;
    }


    public function collection()
    {
        return $this->customers;
    }


    public function headings(): array
    {
        return ['Customer Number', 'Customer Name', 'Type', 'Phone', 'Email', 'Sales Representative', 'Visits', 'Completed Visits', 'Resources Shared', 'Last Visit',];
    }


    public function map($customer): array
    {
        $lastVisit = $customer->visits->max('scheduled_at');


        return [$customer->customer_number,

            $customer->name,

            ucfirst($customer->type ?? ''),

            $customer->phone,

            $customer->email,

            $customer->currentAssignment?->salesRep?->name ?? 'Unassigned',

            $customer->visits_count,

            $customer->completed_visits_count,

            $customer->resource_shares_count,

            $lastVisit ? \Carbon\Carbon::parse($lastVisit)->format('d M Y H:i') : '',];
    }
}
