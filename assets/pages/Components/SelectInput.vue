<template>
  <select v-model="selectedOption">
    <option v-for="option in options" :value="option">
      {{ option }}
    </option>
  </select>
</template>

<script>
export default {
  props: {
    options: {
      type: Array,
      required: true,
    },
    value: {
      type: String,
      default: "",
    },
    id: {
      type: Number,
      default: "",
    },
  },
  data() {
    return {
      selectedOption: this.value,
    };
  },
  watch: {
    selectedOption(value) {
      this.$emit("input", value, this.$props.id);
    },
  },
};
</script>
