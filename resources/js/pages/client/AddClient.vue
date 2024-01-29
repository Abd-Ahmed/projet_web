<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">
            <span v-if="editMode">Edit</span>
            <span v-else>Create</span>
            Client
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <router-link to="/admin/dashboard">Home</router-link>
            </li>
            <li class="breadcrumb-item">
              <router-link to="/admin/clients">Clients</router-link>
            </li>
            <li class="breadcrumb-item active">
              <span v-if="editMode">Edit</span>
              <span v-else>Create</span>
            </li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <Form @submit="handleSubmit" v-slot="{ errors }">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input v-model="form.first_name" type="text" class="form-control" :class="{ 'is-invalid': errors.first_name }" id="first_name" placeholder="Enter First Name">
                    <span class="invalid-feedback">{{ errors.first_name }}</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input v-model="form.last_name" type="text" class="form-control" :class="{ 'is-invalid': errors.last_name }" id="last_name" placeholder="Enter Last Name">
                    <span class="invalid-feedback">{{ errors.last_name }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Enter Email">
                    <span class="invalid-feedback">{{ errors.email }}</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input v-model="form.phone_number" type="text" class="form-control" :class="{ 'is-invalid': errors.phone_number }" id="phone_number" placeholder="Enter Phone Number">
                    <span class="invalid-feedback">{{ errors.phone_number }}</span>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </Form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/themes/light.css';

const router = useRouter();
const route = useRoute();
const toastr = useToastr();

const form = ref({
first_name: '',
last_name: '',
email: '',
phone_number: '',
});

const editMode = ref(false);
const clients = ref([]);

const getClients = () => {
axios.get('/api/clients').then((response) => {
  clients.value = response.data;
});
};

const getClient = () => {
axios.get(`/api/clients/${route.params.id}/edit`).then(({ data }) => {
  form.value.first_name = data.first_name;
  form.value.last_name = data.last_name;
  form.value.email = data.email;
  form.value.phone_number = data.phone_number;
});
};

const handleSubmit = async (values, actions) => {
try {
  if (editMode.value) {
    await axios.put(`/api/clients/${route.params.id}/edit`, form.value);
    toastr.success('Client updated successfully!');
  } else {
    await axios.post('/api/clients', form.value);
    toastr.success('Client created successfully!');
  }
  router.push({name : 'admin.clients'});
} catch (error) {
  actions.setErrors(error.response.data.errors);
}
};

onMounted(() => {
if (route.name === 'admin.clients.edit') {
  editMode.value = true;
  getClient();
}

flatpickr('.flatpickr', {
  enableTime: true,
  dateFormat: 'Y-m-d h:i K',
  defaultHour: 10,
});

getClients();
});
</script>