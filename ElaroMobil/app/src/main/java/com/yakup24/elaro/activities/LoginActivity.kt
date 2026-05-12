package com.yakup24.elaro.activities

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import com.yakup24.elaro.R
import com.yakup24.elaro.models.LoginRequest
import com.yakup24.elaro.models.LoginResponse
import com.yakup24.elaro.network.ApiService
import com.yakup24.elaro.ui.network.RetrofitClient
import com.yakup24.elaro.ui.network.SessionManager
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class LoginActivity : AppCompatActivity() {

    private lateinit var etLoginEmail: EditText
    private lateinit var etLoginPassword: EditText
    private lateinit var btnLogin: Button
    private lateinit var btnGoToRegister: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        etLoginEmail = findViewById(R.id.etLoginEmail)
        etLoginPassword = findViewById(R.id.etLoginPassword)
        btnLogin = findViewById(R.id.btnLogin)
        btnGoToRegister = findViewById(R.id.btnGoToRegister)

        btnLogin.setOnClickListener {
            val email = etLoginEmail.text.toString().trim()
            val password = etLoginPassword.text.toString().trim()

            if (email.isEmpty() || password.isEmpty()) {
                Toast.makeText(this, "Lütfen tüm alanları doldurun", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            val loginRequest = LoginRequest(Eposta = email, Sifre = password)
            val apiService = RetrofitClient.instance.create(ApiService::class.java)

            apiService.loginUser(loginRequest).enqueue(object : Callback<LoginResponse> {
                override fun onResponse(call: Call<LoginResponse>, response: Response<LoginResponse>) {
                    if (response.isSuccessful && response.body() != null) {
                        val user = response.body()!!

                        Log.d("LoginDebug", "Gelen Ad: ${user.Ad}")

                        SessionManager.userId = user.MusteriID
                        user.Ad?.let { SessionManager.firstName = it }
                        user.Soyad?.let { SessionManager.lastName = it }
                        user.Eposta?.let { SessionManager.loginEmail = it }
                        user.accessToken?.let { SessionManager.accessToken = it }

                        Toast.makeText(this@LoginActivity, "Giriş başarılı", Toast.LENGTH_SHORT).show()
                        startActivity(Intent(this@LoginActivity, MainActivity::class.java))
                        finish()
                    } else {
                        Toast.makeText(this@LoginActivity, "Giriş başarısız: ${response.code()}", Toast.LENGTH_LONG).show()
                    }
                }

                override fun onFailure(call: Call<LoginResponse>, t: Throwable) {
                    Toast.makeText(this@LoginActivity, "Hata: ${t.message}", Toast.LENGTH_LONG).show()
                    Log.e("LoginActivity", "Giriş hatası: ${t.message}", t)
                }
            })
        }

        btnGoToRegister.setOnClickListener {
            startActivity(Intent(this, RegisterActivity::class.java))
        }
    }
}
