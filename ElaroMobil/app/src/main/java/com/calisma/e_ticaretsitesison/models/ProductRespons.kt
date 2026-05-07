package com.calisma.e_ticaretsitesison.models

data class ProductResponse(
    val success: Boolean,
    val data: List<Urun> = emptyList(),
    val message: String? = null
)
