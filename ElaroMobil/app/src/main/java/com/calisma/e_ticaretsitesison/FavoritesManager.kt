package com.calisma.e_ticaretsitesison

import com.calisma.e_ticaretsitesison.models.Urun

object FavoritesManager {
    private val favoriteItems = mutableListOf<Urun>()

    fun addToFavorites(urun: Urun) {
        if (!favoriteItems.any { it.urunID == urun.urunID }) {
            favoriteItems.add(urun)
        }
    }

    fun removeFromFavorites(urunID: Int) {
        favoriteItems.removeAll { it.urunID == urunID }
    }

    fun getFavorites(): List<Urun> = favoriteItems

    fun isFavorite(urunID: Int): Boolean {
        return favoriteItems.any { it.urunID == urunID }
    }

    fun clearFavorites() {
        favoriteItems.clear()
    }
}
