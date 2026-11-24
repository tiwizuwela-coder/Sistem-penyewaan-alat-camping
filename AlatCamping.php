
<?php

abstract class AlatCamping
{
    protected string $kode;
    protected string $nama;

    private int $hargaPerHari;
    private int $stok;

    public function __construct($kode, $nama, $hargaPerHari, $stok)
    {
        $this->kode = $kode;
        $this->nama = $nama;
        $this->hargaPerHari = $hargaPerHari;
        $this->stok = $stok;
    }

    public function getKode()
    {
        return $this->kode;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function getHargaPerHari()
    {
        return $this->hargaPerHari;
    }

    public function getStok()
    {
        return $this->stok;
    }

    public function kurangiStok($jumlah)
    {
        if ($jumlah <= 0 || $jumlah > $this->stok) {
            return false;
        }

        $this->stok -= $jumlah;
        return true;
    }

    public function tampilkanInfo()
    {
        echo "{$this->kode} | {$this->nama} | Rp" .
            number_format($this->hargaPerHari, 0, ',', '.') .
            " / hari | Stok: {$this->stok} | " .
            $this->getJenis() . PHP_EOL;
    }

    abstract public function getJenis();
}
