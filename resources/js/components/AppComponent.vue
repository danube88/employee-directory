<template>
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
  import NavbarComponent from './layout/navbar.vue';
  import HierarchyComponent from './HierarchyComponent.vue';
  export default {
    components: {
      HierarchyComponent,
      NavbarComponent
    },
    mounted() {
      console.log('Component mounted.')
    }
  }
</script>
