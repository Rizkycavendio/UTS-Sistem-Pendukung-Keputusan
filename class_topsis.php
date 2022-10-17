<!-- UTS - Sistem Pendukung Keputusan -->
<!-- Kelompok 10 -->
<!-- Muhammad Rizky Cavendio - 20051397011 -->
<!-- Muhammad Kamaluddin Primajaya - 20051397035 -->
<!-- Figo Gymnastiar Farhaan Pratama - 20051397015 -->

<?php

class classTopsis
{
    public $alternatif = array();
    public $kriteria = array();

    public function __construct()
    {
        // data kriteria (hardcode)
        array_push($this->kriteria, array('Jarak dengan pusat kota (km)', 5, 'Cost'));
        array_push($this->kriteria, array('Luas Tanah', 4, 'Benefit'));
        array_push($this->kriteria, array('Harga tanah', 3, 'Cost'));
        array_push($this->kriteria, array('Jarak dari UNESA kampus utama', 2, 'Cost'));
        array_push($this->kriteria, array('Resiko', 3, 'Cost'));

        // data alternatif (hardcode)
        array_push($this->alternatif, array('Tuban', 0.80, 1000, 18, 50, 500));
        array_push($this->alternatif, array('Magetan', 0.40, 1500, 30, 40, 300));
        array_push($this->alternatif, array('Bojonegoro', 0.70, 2000, 20, 20, 450));

        // memanggil function agar data dapat diakses
        $this->pembagiMatrixTernormalisasi();
        $this->matrixTernormalisasi();
        $this->bobot();
        $this->matrixTernormalisasiTerbobot();
        $this->cmaximum();
        $this->cminimum();
        $this->atribut();
        $this->ymaxmin();
        $this->dplusmin();
    }

    // fungsi untuk menghitung pembagi dari rumus matrix ternormalisasi, setelah mendapatkan pembaginya dari masing-masing, maka hasil pembagi tersebut akan digunakan di fungsi di bawah
    public function pembagiMatrixTernormalisasi()
    {
        $this->pembagiMatrixTernormalisasi = array(0, 0, 0, 0, 0);
        // untuk mengambil dan melooping data alternatif sesuai indeksnya
        foreach ($this->alternatif as $a) {
            // untuk menjumlahkan data kriteria dengan indeks tertentu, kemudian dikuadratkan sesuai indeksnya tersebut
            for ($i = 0; $i < count($this->kriteria); $i++) {
                $this->pembagiMatrixTernormalisasi[$i] += pow($a[$i + 1], 2);
            }
        }
        for ($i = 0; $i < count($this->pembagiMatrixTernormalisasi); $i++) {
            // untuk memangakarkan hasil dari penjumlahan dan perpangkatan di atas tadi kemudian dibulatkan 3 bilangan
            $this->pembagiMatrixTernormalisasi[$i] = round(sqrt($this->pembagiMatrixTernormalisasi[$i]), 3);
        }
    }

    // fungsi untuk menghitung matrix ternormalisasi 
    public function matrixTernormalisasi()
    {
        $this->matrixTernormalisasi = array();
        // melooping data alternatif
        foreach ($this->alternatif as $a) {
            // untuk menghitung rumus ternormalisasi, antara pembilang dari masing-masing nilai kriteria dengan pembagiMatrixTernormalisasi yang sudah kita dapatkan pada function pembagiTernormalisasi()
            for ($i = 0; $i < count($this->pembagiMatrixTernormalisasi); $i++) {
                $a[$i + 1] = $a[$i + 1] / $this->pembagiMatrixTernormalisasi[$i];
                $a[$i + 1] = round($a[$i + 1], 3);
            }
            // setelah mendapatkan hasilnya, kita masukkan ke dalam array agar bisa ditampilkan di index
            array_push($this->matrixTernormalisasi, $a);
        }
    }

    public function bobot()
    {
        // membuat bobot ke dalam array
        $this->bobot = array();
        // melooping array kriteria untuk mendapatkan nilai bobot
        foreach ($this->kriteria as $k) {
            // memasukkan bobot ke dalam array
            array_push($this->bobot, $k[1]);
        }
    }

    // fungsi untuk menghitung bobot dikalikan dengan matrix ternormalisasi
    public function matrixTernormalisasiTerbobot()
    {
        // membuat penampung array dari bobot
        $this->matrixTernormalisasiTerbobot = array();
        // melooping data yang sudah ada yakni matrix ternormalisasi
        foreach ($this->matrixTernormalisasi as $n) {
            //proses mengkalikan data dari matrix Ternormalisasi dengan bobot sesuai dengan indexnya
            for ($i = 0; $i < count($this->bobot); $i++) {
                $n[$i + 1] = $n[$i + 1] * $this->bobot[$i];
            }
            // memasukkan hasilnya ke dalam array dengan value dari $n
            array_push($this->matrixTernormalisasiTerbobot, $n);
        }
    }

    // Solusi Ideal Positif minimum (cost) dan maksimum (benefit)
    public function cminimum()
    {
        $this->cminimum = array(10, 10, 10, 10, 10);
        foreach ($this->matrixTernormalisasiTerbobot as $nb) {
            if ($this->cminimum[0] > $nb[1]) $this->cminimum[0] = $nb[1];
            if ($this->cminimum[1] > $nb[2]) $this->cminimum[1] = $nb[2];
            if ($this->cminimum[2] > $nb[3]) $this->cminimum[2] = $nb[3];
            if ($this->cminimum[3] > $nb[4]) $this->cminimum[3] = $nb[4];
            if ($this->cminimum[4] > $nb[5]) $this->cminimum[4] = $nb[5];
        }
    }

    // Solusi ideal negatif minimum (benefit) dan maksimum (cost)
    public function cmaximum()
    {
        $this->cmaximum = array(0, 0, 0, 0, 0);
        foreach ($this->matrixTernormalisasiTerbobot as $nb) {
            if ($this->cmaximum[0] < $nb[1]) $this->cmaximum[0] = $nb[1];
            if ($this->cmaximum[1] < $nb[2]) $this->cmaximum[1] = $nb[2];
            if ($this->cmaximum[2] < $nb[3]) $this->cmaximum[2] = $nb[3];
            if ($this->cmaximum[3] < $nb[4]) $this->cmaximum[3] = $nb[4];
            if ($this->cmaximum[4] < $nb[5]) $this->cmaximum[4] = $nb[5];
        }
    }

    // fungsi untuk memasukkan atribut dengan indeks tertentu yakni 2 ke dalam array
    public function atribut()
    {
        // membuat atribut ke dalam array
        $this->atribut = array();
        // melooping array kriteria untuk mendapatkan nilai atribut
        foreach ($this->kriteria as $k) {
            // memasukkan atribut ke dalam array 
            array_push($this->atribut, $k[2]);
        }
    }

    // menghitung untuk memnentukan ymax dan ymin
    public function ymaxmin()
    {
        $this->ymax = array();
        $this->ymin = array();
        for ($i = 0; $i < count($this->atribut); $i++) {
            // jika atribut dengan indeks tertentu sama dengan benefit, maka push ke dalam array ymax/ymin yang valuenya cmaximum/cminimum dengan indeks tertentu
            if ($this->atribut[$i] == 'Benefit') {
                array_push($this->ymax, $this->cmaximum[$i]);
                array_push($this->ymin, $this->cminimum[$i]);
            }
            //Selainnya jika atribut dengan indeks tertentu sama dengan Cost, maka push ke dalam array ymax/ymin yang valuenya cminimum/cmaximum dengan indeks tertentu
            else if ($this->atribut[$i] == 'Cost') {
                array_push($this->ymax, $this->cminimum[$i]);
                array_push($this->ymin, $this->cmaximum[$i]);
            }
        }
    }

    // fungsi menghitung D+ dan D-
    public function dplusmin()
    {
        $this->dplusmin = array();
        // melooping array hasil matrixTernormalisasi terbobot
        foreach ($this->matrixTernormalisasiTerbobot as $nb) {
            $this->dplus = 0;
            $this->dmin = 0;
            for ($i = 0; $i < count($this->ymax); $i++) {
                // proses menghitung dengan rumus, representasi rumus dari D+
                $this->dplus += pow($this->ymax[$i] - $nb[$i + 1], 2);
                // proses menghitung dengan rumus, representasi rumus dari D+
                $this->dmin += pow($nb[$i + 1] - $this->ymin[$i], 2);
            }
            // membuat variable dengan indeks tertentu untuk dimasukkan ke dalam array
            $nb[6] = round(sqrt($this->dplus), 3);
            $nb[7] = round(sqrt($this->dmin), 3);
            // memasukkan ke dalam array dplusmin, dengan value yang dimasukkan $nb
            array_push($this->dplusmin, $nb);
        }
    }
}
