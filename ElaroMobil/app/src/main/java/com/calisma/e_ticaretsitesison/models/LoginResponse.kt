package com.calisma.e_ticaretsitesison.models

data class LoginResponse(
    val MusteriID: Int,
    val Ad: String?,
    val Soyad: String?,
    val Eposta: String?
)
