<template>
    <li class="list-group-item">
    <div>
      <div class="item" :class="{itemCurcor: isFolder}" @click="toggle">
        <span v-if="isFolder">
          <font-awesome-icon v-if="isOpen" icon="folder-minus" size="lg"/>
          <font-awesome-icon v-if="!isOpen" icon="folder-plus" size="lg"/>
        </span>
        <span v-if="!isFolder"><font-awesome-icon icon="folder" size="lg"/></span>
      </div>
      <div class="Content">
        <h4>{{ item.name_position }}</h4>
        <h5>{{ item.nameWorker }}</h5>
      </div>
    </div>
    <ul class="list-group Container" v-show="isOpen" v-if="isFolder">
      <tree-item
        v-for="(child, index) in item.children[0]"
        :key="index"
        :item="child"
      ></tree-item>
    </ul>
    </li>
</template>

<script>
  export default {
    name:'tree-item',
    props: {
      item: Object
    },
    data () {
      return {
        isOpen: false
      }
    },
    computed: {
      isFolder: function () {
        return this.item.children &&
          this.item.count > 0
      }
    },
    methods: {
      toggle: function () {
        var app = this;
        if (this.isFolder) {
          if(this.$children.length == 0 && this.item.count != 0) {
            var id = this.item.id;
            axios.get('/api/hierarchy/data/tree/' + id)
              .then(function (resp) {
                app.item.children = [resp.data];
            })
              .catch(function (resp) {
                console.log(resp);
            });
          }
          this.isOpen = !this.isOpen
        }
      }
    }
  }
</script>
