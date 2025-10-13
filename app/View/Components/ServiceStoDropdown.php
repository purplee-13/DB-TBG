<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class ServiceStoDropdown extends Component
{
    /**
     * Data service area => list sto
     *
     * @var array
     */
    public $data;

    public function __construct()
    {
        // inisialisasi data di sini
        $this->data = [
            'SA LUWU UTARA' => ['MAS','MLL','TMN'],
            'SA MAJENE'     => ['MAJ','MMS','PLW'],
            'SA MAMUJU'     => ['MAM','PKA','TPY'],
            'SA PALOPO'     => ['BLP','PLP'],
            'SA PAREPARE'   => ['BAR','PRE'],
            'SA PINRANG'    => ['ENR','PIN','SID','TTE'],
            'SA TORAJA'     => ['MAK','RTP'],
            'SA WAJO'       => ['SIW','SKG','WTG'],
        ];
    }

    public function render()
    {
        // secara default public properties tersedia di view,
        // tapi kita juga bisa eksplisit mengirimkannya:
        return view('components.service-sto-dropdown')
               ->with('data', $this->data);
    }
}
