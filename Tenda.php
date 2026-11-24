
<?php

require_once __DIR__ . '/AlatCamping.php';

class Tenda extends AlatCamping
{
    private int $kapasitasOrang;

    public function __construct(
        $kode,
        $nama,
        $hargaPerHari,
        $stok,
        $kapasitasOrang
    ) {
        parent::__construct(
            $kode,
            $nama,
            $hargaPerHari,
            $stok
        );

        $this->kapasitasOrang = $kapasitasOrang;
    }

    public function getJenis()
    {
        return "Tenda untuk {$this->kapasitasOrang} orang";
    }
}
