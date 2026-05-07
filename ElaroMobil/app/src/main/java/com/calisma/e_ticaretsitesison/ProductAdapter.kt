package com.calisma.e_ticaretsitesison

import android.content.Intent
import android.graphics.drawable.Drawable
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageButton
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.bumptech.glide.load.DataSource
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.load.engine.GlideException
import com.bumptech.glide.request.RequestListener
import com.bumptech.glide.request.target.Target
import com.calisma.e_ticaretsitesison.activities.ProductDetailActivity
import com.calisma.e_ticaretsitesison.models.Urun

class ProductAdapter(
    private var uruns: List<Urun>,
    private val onItemClick: (Urun) -> Unit,
    private val onRemoveClick: (Urun) -> Unit
) : RecyclerView.Adapter<ProductAdapter.ProductViewHolder>() {

    inner class ProductViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val imgProduct: ImageView = itemView.findViewById(R.id.imgProduct)
        val tvName: TextView = itemView.findViewById(R.id.tvProductName)
        val tvPrice: TextView = itemView.findViewById(R.id.tvProductPrice)
        val tvStock: TextView = itemView.findViewById(R.id.tvProductStock)
        val btnRemoveFavorite: ImageButton = itemView.findViewById(R.id.btnRemoveFavorite)

        fun bind(urun: Urun) {
            tvName.text = urun.ad ?: "Ürün adı yok"
            tvPrice.text = "₺ %.2f".format(urun.fiyat ?: 0.0)
            tvStock.text = "Stok: ${urun.stokAdedi}"

            Glide.with(itemView.context)
                .load(urun.gorselURL)
                .placeholder(R.drawable.placeholder_resim_background)
                .error(R.drawable.error_resim)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .listener(object : RequestListener<Drawable> {
                    override fun onLoadFailed(
                        e: GlideException?, model: Any?, target: Target<Drawable>?, isFirstResource: Boolean
                    ): Boolean {
                        Log.e("GlideError", "Yükleme hatası: ${e?.message}")
                        return false
                    }

                    override fun onResourceReady(
                        resource: Drawable?, model: Any?, target: Target<Drawable>?, dataSource: DataSource?, isFirstResource: Boolean
                    ): Boolean = false
                })
                .into(imgProduct)

            itemView.setOnClickListener {
                val intent = Intent(itemView.context, ProductDetailActivity::class.java)
                intent.putExtra("selectedProduct", urun)
                itemView.context.startActivity(intent)
            }

            // Favorilerden kaldır
            btnRemoveFavorite.setOnClickListener {
                onRemoveClick(urun)
            }
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ProductViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_product, parent, false)
        return ProductViewHolder(view)
    }

    override fun onBindViewHolder(holder: ProductViewHolder, position: Int) {
        holder.bind(uruns[position])
    }

    override fun getItemCount(): Int = uruns.size

    fun updateList(newUruns: List<Urun>) {
        uruns = newUruns
        notifyDataSetChanged()
    }
}
