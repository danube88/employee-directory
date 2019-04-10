<template>
    <div>
      <navbarAuth-component></navbarAuth-component>
      <br/>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <list-component></list-component>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import NavbarAuthComponent from './navbarAuth.vue';
    import ListComponent from './../ListAuthComponent.vue';
    export default {
        components: {
          'navbarAuth-component': NavbarAuthComponent,
          ListComponent
        }
    }
</script>
<style>
  #navbarSupportedContent > ul.navbar-nav.mr-auto > li.nav-item.router-link-exact-active.router-link-active > a {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
  }
</style>
