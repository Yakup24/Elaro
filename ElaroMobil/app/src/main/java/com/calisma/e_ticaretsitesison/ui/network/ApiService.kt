package com.calisma.e_ticaretsitesison.network

import retrofit2.Call
import retrofit2.http.*
import com.calisma.e_ticaretsitesison.models.Musteri
import com.calisma.e_ticaretsitesison.models.LoginRequest
import com.calisma.e_ticaretsitesison.models.LoginResponse
import com.calisma.e_ticaretsitesison.models.Kategori
import com.calisma.e_ticaretsitesison.models.Urun

interface ApiService {

    @GET("api/Urun")
    fun getAllProducts(): Call<List<Urun>>

    @POST("api/Auth/register")
    fun registerUser(@Body musteri: Musteri): Call<Void>

    @POST("api/Auth/login")
    fun loginUser(@Body request: LoginRequest): Call<LoginResponse>

    @GET("api/Kategori")
    fun getKategoriler(): Call<List<Kategori>>

    @GET("api/Kategori/{id}")
    fun getKategoriById(@Path("id") id: Int): Call<Kategori>


}
