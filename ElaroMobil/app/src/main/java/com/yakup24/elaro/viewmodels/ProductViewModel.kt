package com.yakup24.elaro.viewmodels

import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewModelScope
import com.yakup24.elaro.models.Urun
import com.yakup24.elaro.ui.network.ApiService
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch

sealed class ProductUiState {
    object Loading : ProductUiState()
    data class Success(val products: List<Urun>) : ProductUiState()
    data class Error(val message: String) : ProductUiState()
}

class ProductViewModel(
    private val apiService: ApiService
) : ViewModel() {
    private val _uiState = MutableStateFlow<ProductUiState>(ProductUiState.Loading)
    val uiState: StateFlow<ProductUiState> = _uiState

    init {
        refresh()
    }

    fun refresh() {
        viewModelScope.launch {
            _uiState.value = ProductUiState.Loading

            try {
                _uiState.value = ProductUiState.Success(apiService.getAllProductsSuspend())
            } catch (exception: Exception) {
                _uiState.value = ProductUiState.Error(exception.message ?: "Server error")
            }
        }
    }
}

class ProductViewModelFactory(
    private val apiService: ApiService
) : ViewModelProvider.Factory {
    @Suppress("UNCHECKED_CAST")
    override fun <T : ViewModel> create(modelClass: Class<T>): T {
        if (modelClass.isAssignableFrom(ProductViewModel::class.java)) {
            return ProductViewModel(apiService) as T
        }

        throw IllegalArgumentException("Unknown ViewModel class: ${modelClass.name}")
    }
}
