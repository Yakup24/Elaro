package com.yakup24.elaro.network

import retrofit2.Call
import retrofit2.http.*
import com.yakup24.elaro.models.Musteri
import com.yakup24.elaro.models.LoginRequest
import com.yakup24.elaro.models.LoginResponse
import com.yakup24.elaro.models.Kategori
import com.yakup24.elaro.models.Urun

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
