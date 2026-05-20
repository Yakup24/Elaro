package com.yakup24.elaro.activities

import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.Lifecycle
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.lifecycleScope
import androidx.lifecycle.repeatOnLifecycle
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.yakup24.elaro.ProductAdapter
import com.yakup24.elaro.R
import com.yakup24.elaro.models.Urun
import com.yakup24.elaro.ui.network.ApiService
import com.yakup24.elaro.ui.network.RetrofitClient
import com.yakup24.elaro.viewmodels.ProductUiState
import com.yakup24.elaro.viewmodels.ProductViewModel
import com.yakup24.elaro.viewmodels.ProductViewModelFactory
import kotlinx.coroutines.launch

class ProductListActivity : AppCompatActivity() {

    private lateinit var etMinPrice: EditText
    private lateinit var etMaxPrice: EditText
    private lateinit var btnApplyFilter: Button
    private lateinit var etSearch: EditText
    private lateinit var rvProducts: RecyclerView

    private lateinit var productAdapter: ProductAdapter
    private var allUruns = listOf<Urun>()
    private lateinit var viewModel: ProductViewModel

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

        val apiService = RetrofitClient.instance.create(ApiService::class.java)
        viewModel = ViewModelProvider(
            this,
            ProductViewModelFactory(apiService)
        )[ProductViewModel::class.java]

        lifecycleScope.launch {
            repeatOnLifecycle(Lifecycle.State.STARTED) {
                viewModel.uiState.collect { state ->
                    when (state) {
                        ProductUiState.Loading -> Unit
                        is ProductUiState.Success -> {
                            allUruns = state.products
                            applyCurrentFilters()
                        }
                        is ProductUiState.Error -> {
                            Toast.makeText(this@ProductListActivity, state.message, Toast.LENGTH_LONG).show()
                        }
                    }
                }
            }
        }

        btnApplyFilter.setOnClickListener {
            applyCurrentFilters()
        }

        etSearch.addTextChangedListener(object : TextWatcher {
            override fun afterTextChanged(s: Editable?) {
                applyCurrentFilters()
            }

            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) = Unit
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) = Unit
        })
    }

    private fun applyCurrentFilters() {
        val query = etSearch.text.toString().trim().lowercase()
        val min = etMinPrice.text.toString().toDoubleOrNull()
        val max = etMaxPrice.text.toString().toDoubleOrNull()

        val filtered = allUruns.filter { urun ->
            val matchesSearch = query.isEmpty() || urun.ad?.lowercase()?.contains(query) == true
            val fiyat = urun.fiyat ?: 0.0
            val matchesMin = min == null || fiyat >= min
            val matchesMax = max == null || fiyat <= max

            matchesSearch && matchesMin && matchesMax
        }

        productAdapter.updateList(filtered)
    }
}
