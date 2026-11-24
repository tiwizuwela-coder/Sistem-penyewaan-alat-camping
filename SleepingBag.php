
<?php

require_once __DIR__ . '/AlatCamping.php';

class SleepingBag extends AlatCamping
{
    private string $bahan;

    public function __construct(
        $kode,
        $nama,
        $hargaPerHari,
        $stok,
        $bahan
    ) {
        parent::__construct(
            $kode,
            $nama,
            $hargaPerHari,
            $stok
        );

        $this->bahan = $bahan;
    }

    public function getJenis()
    {
        return "Sleeping bag berbahan {$this->bahan}";
    }
}
