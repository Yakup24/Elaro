package com.yakup24.elaro.models

import java.io.Serializable

data class CartItem(
    val urun: Urun,
    var quantity: Int
): Serializable