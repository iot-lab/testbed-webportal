<template>
  <div id="app">
    <navbar></navbar>

    <notifications position="top right" :duration="3000" animation-type="velocity" style="margin: 10px;">
      <template slot="body" slot-scope="props" style="margin: 10px">
        <!-- <div :class="['alert', `alert-${props.item.type==='error'?'danger':props.item.type}`]" @click="props.close"> -->
        <div class="alert text-light border-0" :class="[`bg-${props.item.type==='error'?'danger':props.item.type}`]" @click="props.close">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="font-weight: normal; font-size: 1.3rem; text-shadow: none">
            <span aria-hidden="true">&times;</span>
          </button>
          <h6 class="alert-heading" v-if="props.item.title" v-html="props.item.title"></h6>
          <div v-html="props.item.text" style="line-height: 1.3"></div>
        </div>
      </template>
    </notifications>

    <notifications group="alt" position="top center" :duration="-1" animation-type="css" style="margin-top: 6px;">
      <template slot="body" slot-scope="props" style="margin-top: 10px">
        <div class="alert alert-secondary text-center">
          <h6 class="alert-heading" v-if="props.item.title" v-html="props.item.title"></h6>
          <div v-html="props.item.text" style="line-height: 1.3"></div>
        </div>
      </template>
    </notifications>

    <notifications group="popup" position="top left" :duration="-1" animation-type="css" style="width: 100%">
      <template slot="body" slot-scope="props">
        <div class="alert-popup">
          <div class="card">
            <div class="card-body">
              <h3 class="card-title">We care about knowing our users</h3>
              <p class="card-text">Please take a minute to fill your profile with your <i><b>user category</b></i> (student, academic, startup, industrial...)</p>
              <router-link :to="{name:'account', query: {validate: null}}" class="btn btn-primary" @click.native="props.close">Go to my profile</router-link>
              <a href="#" class="btn btn-link" @click="props.close">Later</a>
            </div>
          </div>
        </div>
      </template>
    </notifications>

    <keep-alive include="NewExperiment,AdminUsers,Dashboard">
      <router-view></router-view>
    </keep-alive>
  </div>
</template>

<script>
import Navbar from '@/components/Navbar'

export default {
  name: 'app',
  components: { Navbar },
}
</script>

<style>
.text-capitalize-first:first-letter {
  text-transform: uppercase;
}
.cursor:hover {
  cursor: pointer;
}
.cursor-help:hover {
  cursor: help;
}
.v-select .dropdown-toggle {
  padding: 1px !important; /* adjust v-select to bootstrap-4 */
}
.scrollable {
  overflow-y: auto !important;
  /*overflow-x: hidden !important;*/
  /*margin-bottom: 20px;*/
}
.scrollable.h200 {
  max-height: 200px;
}
.scrollable.h300 {
  max-height: 300px;
}
.scrollable.h400 {
  max-height: 400px;
}
.scrollable.h500 {
  max-height: 500px;
}
.scrollable.h600 {
  max-height: 600px;
}
.alert-popup {
  width: 100vw;
  height: 100vh;
  background-color: #868e96cc;
  display: flex;
  justify-content: center;
  align-items: flex-start;
}
.alert-popup .card {
  max-width: 80%;
  min-width: 500px;
  box-shadow: 0px 0px 50px 2px #222;
  padding: 1.5em 2.5em;
  margin-top: 200px;
}
a:focus {
  outline: none;
}
</style>
