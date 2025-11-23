<template id="choiceofpayment-dialog" v-cloak>
  <div class="choiceofpayment-dialog">
    <el-dialog custom-class="mobileWidth"
               title="{{ __('shop/checkout.payment_method') }}"
               :visible.sync="show"
               @close="close"
               :close-on-click-modal="false">
      <div class="radio-line-wrap">
        <div v-for="item in methods"
             :key="item.code"
             class="radio-line-item"
             :class="selectedCode === item.code ? 'active' : ''"
             @click="select(item)">
          <div class="left">
            <!-- <span class="radio"></span> -->
            <img :src="item.icon" class="img-fluid" :alt="item.name">
          </div>
          <div class="right ms-2 d-flex flex-row">
            <div class="title">@{{ item.name }}</div>
            <div class="chevron"><i class="bi bi-chevron-right"></i></div>
          </div>
          
        </div>
      </div>
      <div class="mt-3">
        <el-button type="primary" @click="confirm">{{ __('common.save') }}</el-button>
        <el-button @click="close">{{ __('common.cancel') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
Vue.component('choiceofpayment-dialog', {
  template: '#choiceofpayment-dialog',
  props: {
    value: { default: null }
  },
  data: function () {
    return {
      show: false,
      methods: [],
      selectedCode: '',
      onchange: null
    }
  },
  methods: {
    open(methods, currentCode, onchange) {
      this.methods = Array.isArray(methods) ? methods : [];
      this.selectedCode = currentCode || '';
      this.show = true;
      this.onchange = onchange
    },
    select(item) {
      this.selectedCode = item.code;
    },
    confirm() {
      var selected = this.methods.find(m => m.code === this.selectedCode) || null;
      this.$emit('change', selected);
      this.show = false;
      this.onchange && this.onchange(selected);
    },
    close() {
      this.show = false;
    }
  }
});
</script>