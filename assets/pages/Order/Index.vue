<script setup>
import { ref } from "vue";
import Pagination from "../Components/Pagination.vue";
import SelectInput from "../Components/SelectInput.vue";
const props = defineProps({
  totalPages: Number,
  page: Number,
  orders: Object,
  statusStates: Array,
});

const base_url = ref(window.location.origin);

const changePage = (page) => {
  if (page >= 1 && page <= props.totalPages) {
    // Pentru ca din nou nu am putut folosi inertiaJs sau pachetul Router (nu am stiut sa-l configurez cum trebuie)
    // Am ales sa folosesc JavaScript nativ
    // Imi dau seama ca nu este ideal, dar functioneaza
    var newUrl = base_url.value + window.location.pathname + "?page=" + page;

    window.location.href = newUrl;
  }
};

const formatData = (date) => {
  return new Date(date).toLocaleDateString("ro-RO", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const openDialogBox = async (value, id) => {
  if (props.statusStates.includes(value)) {
    if (confirm("Are you sure you want to cancel your order?")) {
      // Pentru ca nu am putut sa initiez cum trebuie InertiaJs sa ma pot folosi de numele rutei si de
      // metoda put de la inertia, am decis sa folosesc JavaScript, si am hardcodat ruta
      const requestOptions = {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
      };

      var base_url = window.location.origin;

      const response = await fetch(
        `${base_url}/api/cancel-order/${id}`,
        requestOptions
      );
      const data = await response.json();

      if (response.ok) {
        location.reload();
      }
    }
  }
};
</script>

<template>
  <div class="table-wrapper">
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Date</th>
          <th>Customer</th>
          <th>Address1</th>
          <th>City</th>
          <th>Postcode</th>
          <th>Amount</th>
          <th>Country</th>
          <th>Status</th>
          <th>Deleted</th>
          <th>Last Modified</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in props.orders" :key="index">
          <td>{{ item.id }}</td>
          <td>{{ formatData(item.date) }}</td>
          <td>{{ item.customer }}</td>
          <td>{{ item.address1 }}</td>
          <td>{{ item.city }}</td>
          <td>{{ item.postcode }}</td>
          <td>{{ item.country }}</td>
          <td>{{ item.amount }}</td>
          <td v-if="item.status == 'pending'">
            <SelectInput
              :options="props.statusStates"
              :value="item.status"
              :id="item.id"
              @input="openDialogBox"
            />
          </td>
          <td v-else>
            {{ item.status }}
          </td>
          <td>{{ item.deleted }}</td>
          <td>{{ formatData(item.date) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <Pagination
    @page-changed="changePage"
    :currentPage="props.page"
    :totalPages="props.totalPages"
  />
</template>

<style scoped>
.table-wrapper {
  overflow-x: auto;
  max-width: 100%;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #212529;
  background-color: #fff;
}

.table th,
.table td {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}

.table th {
  font-weight: 700;
  text-align: inherit;
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
  border-top: 2px solid #dee2e6;
}

.table-sm th,
.table-sm td {
  padding: 0.3rem;
}

.table-bordered {
  border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
  background-color: rgba(0, 0, 0, 0.075);
}

.table-primary,
.table-primary > th,
.table-primary > td {
  background-color: #b8daff;
}

.table-primary th,
.table-primary td,
.table-primary thead th,
.table-primary tbody + tbody {
  border-color: #7abaff;
}
</style>
