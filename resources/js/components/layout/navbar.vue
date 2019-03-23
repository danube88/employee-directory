<template>
  <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
      <router-link to="/" class="navbar-brand">
        <a class="navbar-brand">Employee directory</a>
      </router-link>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav nav-pills mr-auto">
          <router-link tag="li" to="/hierarchy" class="nav-item">
            <a class="nav-link">Иерархия сотрудников</a>
          </router-link>
          <router-link tag="li" to="/list" class="nav-item">
            <a class="nav-link">Список сотрудников</a>
          </router-link>
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
          <router-link tag="li" to="/login" class="nav-item">
            <a class="nav-link">Войти</a>
          </router-link>
          <router-link tag="li" to="/register" class="nav-item">
            <a class="nav-link">Регистрация</a>
          </router-link>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style>
  #navbarSupportedContent > ul.navbar-nav.ml-auto > li.nav-item.router-link-exact-active.router-link-active > a {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
  }
</style>
