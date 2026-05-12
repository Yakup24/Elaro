package com.yakup24.elaro

import com.yakup24.elaro.models.CartItem
import com.yakup24.elaro.models.Urun

object CartManager {
    val items = mutableListOf<CartItem>()

    fun addToCart(urun: Urun) {
        val existing = items.find { it.urun.urunID == urun.urunID }
        if (existing != null) {
            existing.quantity += 1
        } else {
            items.add(CartItem(urun, 1))
        }
    }

    fun removeFromCart(productId: Int) {
        items.removeAll { it.urun.urunID == productId }
    }

    fun clearCart() {
        items.clear()
    }

    fun getTotalPrice(): Double {
        return items.sumOf { (it.urun.fiyat ?: 0.0) * it.quantity }
    }
}
