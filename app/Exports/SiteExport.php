<?php

namespace App\Exports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiteExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = Site::query();

        // Jika ada pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('site_code', 'like', "%{$this->search}%")
                  ->orWhere('site_name', 'like', "%{$this->search}%")
                  ->orWhere('service_area', 'like', "%{$this->search}%");
            });
        }

        return $query->get();
    }

    public function map($site): array
    {
        return [
            $site->site_code,
            $site->site_name,
            $site->service_area,
            $site->sto,
            $site->product,
            $site->tikor,
            $site->progres ?? '-',
            $site->teknisi ?? '-',
            $site->tgl_visit ?? '-',
            $site->operator  ?? '-',
            $site->keterangan ?? '-',

        ];
    }

    public function headings(): array
    {
        return [
            'Site Code',
            'Site Name',
            'Service Area',
            'STO',
            'Product',
            'TIKOR',
            'Progres',
            'Teknisi',
            'Tgl Visit',
            'Operator',
            'Keterangan',
        ];
    }
}
