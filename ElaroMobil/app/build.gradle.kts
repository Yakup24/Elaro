plugins {
    alias(libs.plugins.android.application)
    alias(libs.plugins.kotlin.android)
    alias(libs.plugins.kotlin.compose)

    // Bu satırı değiştirdik:
    id("kotlin-parcelize") // ✅ alias kullanmadan, doğru kullanım
    kotlin("kapt")
}

val apiBaseUrl = providers.gradleProperty("ELARO_API_BASE_URL")
    .orElse(providers.environmentVariable("ELARO_API_BASE_URL"))
    .orElse("http://10.0.2.2:5218/")
    .get()
val normalizedApiBaseUrl = if (apiBaseUrl.endsWith("/")) apiBaseUrl else "$apiBaseUrl/"

android {
    namespace = "com.calisma.e_ticaretsitesison"
    compileSdk = 36

    defaultConfig {
        applicationId = "com.calisma.e_ticaretsitesison"
        minSdk = 24
        targetSdk = 35
        versionCode = 1
        versionName = "1.0"
        testInstrumentationRunner = "androidx.test.runner.AndroidJUnitRunner"
        buildConfigField("String", "API_BASE_URL", "\"$normalizedApiBaseUrl\"")
    }

    buildTypes {
        release {
            isMinifyEnabled = false
            proguardFiles(
                getDefaultProguardFile("proguard-android-optimize.txt"),
                "proguard-rules.pro"
            )
        }
    }

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }

    buildFeatures {
        buildConfig = true
        compose = true
    }
}

kotlin {
    compilerOptions {
        jvmTarget.set(org.jetbrains.kotlin.gradle.dsl.JvmTarget.JVM_11)
    }
}

dependencies {
    implementation(libs.androidx.cardview)
    implementation(libs.androidx.core.ktx)
    implementation(libs.androidx.lifecycle.runtime.ktx)
    implementation(libs.androidx.activity.compose)
    implementation(platform(libs.androidx.compose.bom))
    implementation(libs.androidx.ui)
    implementation(libs.androidx.ui.graphics)
    implementation(libs.androidx.ui.tooling.preview)
    implementation(libs.androidx.material3)
    implementation(libs.androidx.appcompat)
    implementation(libs.material)
    implementation(libs.androidx.constraintlayout)
    implementation(libs.androidx.ui.tooling)
    implementation(libs.androidx.ui.test.manifest)

    // Glide
    implementation(libs.glide)
    kapt("com.github.bumptech.glide:compiler:5.0.7")

    // Volley
    implementation("com.android.volley:volley:1.2.1")

    // OkHttp & Gson
    implementation("com.squareup.okhttp3:okhttp:5.3.2")
    implementation("com.google.code.gson:gson:2.14.0")

    // Retrofit + GSON Converter
    implementation("com.squareup.retrofit2:retrofit:3.0.0")
    implementation("com.squareup.retrofit2:converter-gson:3.0.0")

    // Testler
    testImplementation(libs.junit)
    androidTestImplementation(libs.androidx.junit)
    androidTestImplementation(libs.androidx.espresso.core)
    androidTestImplementation(platform(libs.androidx.compose.bom))
    androidTestImplementation(libs.androidx.ui.test.junit4)
}
