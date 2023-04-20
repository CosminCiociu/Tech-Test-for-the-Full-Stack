<template>
  <div class="pagination">
    <a @click.prevent="prevPage()" v-if="currentPage !== 1">previous</a>

    <a
      v-for="page in pages"
      :key="page"
      :class="{ active: page === currentPage }"
      @click.prevent="goToPage(page)"
      >{{ page }}</a
    >

    <a @click.prevent="nextPage()" v-if="currentPage !== totalPages">next</a>
  </div>
</template>

<script>
export default {
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    totalPages: {
      type: Number,
      required: true,
    },
    pageRange: {
      type: Number,
      default: 5,
    },
  },
  computed: {
    pages() {
      let start = Math.max(1, this.currentPage - this.pageRange);
      let end = Math.min(this.totalPages, this.currentPage + this.pageRange);

      if (end - start < this.pageRange * 2) {
        if (start === 1) {
          end = Math.min(this.totalPages, start + this.pageRange * 2);
        } else {
          start = Math.max(1, end - this.pageRange * 2);
        }
      }

      return Array(end - start + 1)
        .fill()
        .map((_, i) => start + i);
    },
  },
  methods: {
    goToPage(page) {
      if (page !== this.currentPage) {
        this.$emit("page-changed", page);
      }
    },
    prevPage() {
      this.goToPage(this.currentPage - 1);
    },
    nextPage() {
      this.goToPage(this.currentPage + 1);
    },
  },
};
</script>

<style scoped>
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 1rem;
}

.pagination a {
  display: inline-block;
  padding: 0.5rem;
  margin: 0 0.25rem;
  border: 1px solid #ccc;
  border-radius: 0.25rem;
  cursor: pointer;
  user-select: none;
}

.pagination a.disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination a.active {
  background-color: #007bff;
  color: #fff;
}
</style>
