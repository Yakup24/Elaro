package com.yakup24.elaro.activities

import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.yakup24.elaro.ProductAdapter
import com.yakup24.elaro.R
import com.yakup24.elaro.models.Urun
import com.yakup24.elaro.network.ApiService
import com.yakup24.elaro.ui.network.RetrofitClient
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ProductListActivity : AppCompatActivity() {

    private lateinit var etMinPrice: EditText
    private lateinit var etMaxPrice: EditText
    private lateinit var btnApplyFilter: Button
    private lateinit var etSearch: EditText
    private lateinit var rvProducts: RecyclerView

    private lateinit var productAdapter: ProductAdapter
    private var allUruns = listOf<Urun>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_product_list)

        etMinPrice = findViewById(R.id.etMinPrice)
        etMaxPrice = findViewById(R.id.etMaxPrice)
        btnApplyFilter = findViewById(R.id.btnApplyFilter)
        etSearch = findViewById(R.id.etSearch)
        rvProducts = findViewById(R.id.rvProducts)

        rvProducts.layoutManager = LinearLayoutManager(this)
        productAdapter = ProductAdapter(
            emptyList(),
            onItemClick = { product ->
                val intent = Intent(this, ProductDetailActivity::class.java)
                intent.putExtra("selectedProduct", product)
                startActivity(intent)
            },
            onRemoveClick = {}
        )
        rvProducts.adapter = productAdapter

        btnApplyFilter.setOnClickListener {
            applyFilter()
        }

        etSearch.addTextChangedListener(object : TextWatcher {
            override fun afterTextChanged(s: Editable?) {
                val query = s.toString().lowercase()
                val filtered = allUruns.filter { it.ad?.lowercase()?.contains(query) == true }
                productAdapter.updateList(filtered)
            }

            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {}
        })

        loadProductsFromApi()
    }

    private fun loadProductsFromApi() {
        val apiService = RetrofitClient.instance.create(ApiService::class.java)
        apiService.getAllProducts().enqueue(object : Callback<List<Urun>> {
            override fun onResponse(call: Call<List<Urun>>, response: Response<List<Urun>>) {
                if (response.isSuccessful && response.body() != null) {
                    allUruns = response.body()!!
                    productAdapter.updateList(allUruns)
                } else {
                    Toast.makeText(this@ProductListActivity, "Ürünler getirilemedi", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onFailure(call: Call<List<Urun>>, t: Throwable) {
                Toast.makeText(this@ProductListActivity, "Sunucu hatası: ${t.message}", Toast.LENGTH_LONG).show()
            }
        })
    }

    private fun applyFilter() {
        val min = etMinPrice.text.toString().toDoubleOrNull()
        val max = etMaxPrice.text.toString().toDoubleOrNull()

        val filtered = allUruns.filter { urun ->
            val fiyat = urun.fiyat ?: 0.0
            (min == null || fiyat >= min) && (max == null || fiyat <= max)
        }


        productAdapter.updateList(filtered)
    }
}
