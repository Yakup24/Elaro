package com.calisma.e_ticaretsitesison.models

data class Kategori(
    val kategoriID: Int,
    val ad: String,
    val aciklama: String,
    val ustKategoriID: Int? = null // bu kısım önemli
)


