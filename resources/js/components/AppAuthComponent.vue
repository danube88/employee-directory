c<template>
  <div>
    <navbar-component></navbar-component>
      <br/>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <hierarchy-component></hierarchy-component>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
  import NavbarComponent from './layout/navbarAuth.vue';
  import HierarchyComponent from './HierarchyComponent.vue';
  export default {
    components: {
      HierarchyComponent,
      NavbarComponent
    }
  }
</script>
