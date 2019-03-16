<template>
  <div>
    <p><h2>Список сотрудников</h2></p>
  </div>
</template>

<script>
  export default {
    mounted() {
      this.isHierarchy = false;
      this.isList = true;
      console.log('Component mounted.');
    }
  }
</script>
