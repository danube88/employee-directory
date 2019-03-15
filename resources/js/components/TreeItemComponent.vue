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
        <table class="table table-sm table-borderless">
          <tbody>
            <tr>
              <td>таб. номер:</td>
              <td><h5>{{ item.table_number }}</h5></td>
            </tr>
            <tr>
              <td>Фамилия Имя Отчество: </td>
              <td><h4>{{ item.nameWorker }}</h4></td>
            </tr>
            <tr>
              <td>Должность:</td>
              <td>{{ item.name_position }}</td>
            </tr>
            <tr>
              <td>Дата приема на работу:</td>
              <td>{{ item.reception_date }}</td>
            </tr>
            <tr>
              <td>Размер заработной платы:</td>
              <td>{{ item.salary }} руб.</td>
            </tr>
          </tbody>
        </table>
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
          this.item.children.length
      }
    },
    methods: {
      toggle: function () {
        if (this.isFolder) {
          this.isOpen = !this.isOpen
        }
      }
    }
  }
</script>
