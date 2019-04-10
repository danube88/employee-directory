<template>
  <div>
    <div class="loading" v-if="loading">
      {{ textLoading }}
    </div>
    <p><h2>Дерево сотрудников</h2></p>

    <ul class="list-group" id="tree" v-for="tree in treeData" :key="tree.id">
      <tree-item :item="tree">
      </tree-item>
    </ul>
  </div>
</template>

<script>
  import TreeItem from './TreeItemComponent.vue'
  export default {
    name: 'tree',
    components: { 'tree-item' : TreeItem },
    data () {
      return {
        treeData: {},
        loading: false,
        textLoading: 'Загрузка....'
      }
    },
    mounted() {
      var app = this;
      app.loading = true;
      axios.get('/api/hierarchy/data/index')
        .then(function (resp) {
          app.treeData = resp.data;
          app.loading = false;
        })
        .catch(function (resp) {
          console.log(resp);
        });
    },
    methods: {
    }
  }
</script>

<style>
  .item {
    display: block;
    position: absolute;
  }
  .itemCurcor {
    cursor: pointer;
  }
  .Content {
    min-height: 18px;
    margin-left:36px;
  }
  .Content h4{
    font-weight: bold;
  }

  * html .Content {
    height: 18px;
  }
  #tree li {
    padding-right: 0rem;
  }
  .loading {
    z-index: 1;
    margin-top: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
  }
  #navbarSupportedContent > ul.navbar-nav.mr-auto > li.nav-item.router-link-exact-active.router-link-active > a {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
  }
</style>
