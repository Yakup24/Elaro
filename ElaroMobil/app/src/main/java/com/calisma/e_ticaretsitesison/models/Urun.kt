package com.calisma.e_ticaretsitesison.models

import android.os.Parcelable
import kotlinx.parcelize.Parcelize
import com.google.gson.annotations.SerializedName

@Parcelize
data class Urun(
    @SerializedName("urunID")
    val urunID: Int = 0,

    @SerializedName("ad")
    val ad: String? = null,

    @SerializedName("aciklama")
    val aciklama: String? = null,

    @SerializedName("fiyat")
    val fiyat: Double? = null,

    @SerializedName("stokAdedi")
    val stokAdedi: Int? = null,

    @SerializedName("kategoriID")
    val kategoriID: Int? = null,

    @SerializedName("marka")
    val marka: String? = null,

    @SerializedName("renk")
    val renk: String? = null,

    @SerializedName("gorselURL")
    val gorselURL: String? = null
) : Parcelable

