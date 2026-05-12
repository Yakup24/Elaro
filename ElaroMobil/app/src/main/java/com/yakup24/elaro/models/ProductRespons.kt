package com.yakup24.elaro.models

data class ProductResponse(
    val success: Boolean,
    val data: List<Urun> = emptyList(),
    val message: String? = null
)
