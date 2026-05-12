package com.yakup24.elaro

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageButton
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.yakup24.elaro.models.CartItem

class CartAdapter(
    private val cartItems: MutableList<CartItem>,
    private val onRemoveItem: (CartItem) -> Unit
) : RecyclerView.Adapter<CartAdapter.CartViewHolder>() {

    inner class CartViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val imgProduct: ImageView = itemView.findViewById(R.id.imgCartItem)
        val tvName: TextView = itemView.findViewById(R.id.tvCartItemName)
        val tvDetails: TextView = itemView.findViewById(R.id.tvCartItemDetails)
        val btnRemove: ImageButton = itemView.findViewById(R.id.btnRemoveItem)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): CartViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_cart, parent, false)
        return CartViewHolder(view)
    }

    override fun onBindViewHolder(holder: CartViewHolder, position: Int) {
        val cartItem = cartItems[position]
        val product = cartItem.urun

        // Null güvenliği
        holder.tvName.text = product.ad ?: "Ürün Adı"
        val fiyat = product.fiyat ?: 0.0
        val miktar = cartItem.quantity
        holder.tvDetails.text = "Adet: $miktar  Fiyat: ₺%.2f".format(fiyat * miktar)

        Glide.with(holder.itemView.context)
            .load(product.gorselURL ?: "")
            .placeholder(android.R.color.darker_gray)
            .error(android.R.drawable.ic_dialog_alert)
            .into(holder.imgProduct)

        holder.btnRemove.setOnClickListener {
            onRemoveItem(cartItem)
        }
    }

    override fun getItemCount(): Int = cartItems.size

    fun removeItem(item: CartItem) {
        val index = cartItems.indexOf(item)
        if (index != -1) {
            cartItems.removeAt(index)
            notifyItemRemoved(index)
        }
    }
}
