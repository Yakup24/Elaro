package com.calisma.e_ticaretsitesison.models

import java.io.Serializable

data class CartItem(
    val urun: Urun,
    var quantity: Int
): Serializable