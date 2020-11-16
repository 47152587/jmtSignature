<template>
 <select class="browser-default custom-select">
  <option selected>{{title}}</option>
 <option v-for="item in users" v-bind:key="item.id">
   {{item.nombre}}
 </option>
</select> 
</template>
<script>

export default {
  props: {
    title: {
      type: String,
      default: ""
    },
    users: {
      type: Array,
      default: "{}"
    }
  },
  /*computed: {
            dataObj: function() {
                return JSON.parse(this.users);
            }
        },*/
  mounted: function () {
    console.log(this.title);
    //console.log(this.dataObj);
  },
  name: "ddown",
};
</script>
