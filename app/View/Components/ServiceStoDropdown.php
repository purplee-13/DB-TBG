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

    /**
     * Selected service area
     *
     * @var string|null
     */
    public $selectedServiceArea;
    
    /**
     * Selected STO
     *
     * @var string|null
     */
    public $selectedSto;

    public function __construct($selectedServiceArea = null, $selectedSto = null)
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
        $this->selectedServiceArea = $selectedServiceArea;
        $this->selectedSto = $selectedSto;
    }

    public function render()
    {
        // secara default public properties tersedia di view,
        // tapi kita juga bisa eksplisit mengirimkannya:
        return view('components.service-sto-dropdown')
            ->with('data', $this->data)
            ->with('selectedServiceArea', $this->selectedServiceArea)
            ->with('selectedSto', $this->selectedSto);
    }
}
