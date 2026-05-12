package com.yakup24.elaro.activities

import android.app.DatePickerDialog
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import com.yakup24.elaro.R
import com.yakup24.elaro.models.Musteri
import com.yakup24.elaro.network.ApiService
import com.yakup24.elaro.ui.network.RetrofitClient
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.util.*

class RegisterActivity : AppCompatActivity() {

    private lateinit var etAd: EditText
    private lateinit var etSoyad: EditText
    private lateinit var etEmail: EditText
    private lateinit var etTelefon: EditText
    private lateinit var etPassword: EditText
    private lateinit var etDogumTarihi: EditText
    private lateinit var rgGender: RadioGroup
    private lateinit var btnRegister: Button
    private lateinit var btnCalendar: ImageView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        etAd = findViewById(R.id.etAd)
        etSoyad = findViewById(R.id.etSoyad)
        etEmail = findViewById(R.id.etEmail)
        etTelefon = findViewById(R.id.etTelefon)
        etPassword = findViewById(R.id.etPassword)
        etDogumTarihi = findViewById(R.id.etDogumTarihi)
        rgGender = findViewById(R.id.rgGender)
        btnRegister = findViewById(R.id.btnRegister)
        btnCalendar = findViewById(R.id.btnCalendar)

        btnCalendar.setOnClickListener {
            val calendar = Calendar.getInstance()
            val year = calendar.get(Calendar.YEAR)
            val month = calendar.get(Calendar.MONTH)
            val day = calendar.get(Calendar.DAY_OF_MONTH)

            val datePickerDialog = DatePickerDialog(
                this,
                { _, selectedYear, selectedMonth, selectedDay ->
                    val formattedDate = String.format("%04d-%02d-%02d", selectedYear, selectedMonth + 1, selectedDay)
                    etDogumTarihi.setText(formattedDate)
                },
                year, month, day
            )
            datePickerDialog.show()
        }

        btnRegister.setOnClickListener {
            val ad = etAd.text.toString().trim()
            val soyad = etSoyad.text.toString().trim()
            val eposta = etEmail.text.toString().trim()
            val telefon = etTelefon.text.toString().trim()
            val sifre = etPassword.text.toString().trim()
            val dogumTarihi = etDogumTarihi.text.toString().trim()
            val cinsiyet = when (rgGender.checkedRadioButtonId) {
                R.id.rbMale -> "Erkek"
                R.id.rbFemale -> "Kadın"
                else -> ""
            }

            if (ad.isEmpty() || soyad.isEmpty() || eposta.isEmpty() || telefon.isEmpty()
                || sifre.isEmpty() || cinsiyet.isEmpty() || dogumTarihi.isEmpty()) {
                Toast.makeText(this, "Tüm alanları doldurun", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            val musteri = Musteri(
                Ad = ad,
                Soyad = soyad,
                Eposta = eposta,
                Telefon = telefon,
                Sifre = sifre,
                Cinsiyet = cinsiyet,
                DogumTarihi = dogumTarihi
            )

            val apiService = RetrofitClient.instance.create(ApiService::class.java)
            apiService.registerUser(musteri).enqueue(object : Callback<Void> {
                override fun onResponse(call: Call<Void>, response: Response<Void>) {
                    Log.d("RegisterDebug", "onResponse çalıştı")
                    Log.d("RegisterDebug", "Kod: ${response.code()}")
                    Log.d("RegisterDebug", "Body: ${response.body()}")

                    if (response.isSuccessful) {
                        Toast.makeText(this@RegisterActivity, "Kayıt başarılı", Toast.LENGTH_SHORT).show()
                        startActivity(Intent(this@RegisterActivity, LoginActivity::class.java))
                        finish()
                    } else {
                        Toast.makeText(this@RegisterActivity, "Kayıt başarısız: ${response.code()}", Toast.LENGTH_SHORT).show()
                    }
                }

                override fun onFailure(call: Call<Void>, t: Throwable) {
                    Log.e("RegisterDebug", "onFailure: ${t.message}", t)
                    Toast.makeText(this@RegisterActivity, "Sunucu hatası: ${t.message}", Toast.LENGTH_LONG).show()
                }
            })
        }
    }
}
