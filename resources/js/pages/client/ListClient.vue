<script setup>
import { onMounted, ref, computed } from 'vue';
import Swal from 'sweetalert2';
import axios from 'axios';

const clients = ref([]);
const getClients = () => {
    axios.get('/api/clients')
        .then((response) => {
            clients.value = response.data;
        })
};

const deleteClient = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/clients/${id}`)
                .then(() => {
                    const index = clients.value.findIndex(client => client.id === id);
                    if (index !== -1) {
                        clients.value.splice(index, 1);
                    }
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                     router.push({ name: 'admin.clients' }); // Commented out, as it seems unrelated
                });
        }
    })
};


onMounted(() => {
    getClients();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Patients</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Patients</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <router-link to="/admin/clients/create">
                                <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New
                                    Patient</button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">phone</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(client, index) in clients" :key="client.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ client.first_name }}</td>
                                        <td>{{ client.last_name }}</td>
                                        <td>{{ client.email }}</td>
                                        <td>{{ client.phone_number }}</td>

                                        <td>
                                            <router-link :to="`/admin/clients/${client.id}/edit`">
                                                <i class="fa fa-edit mr-2"></i>
                                            </router-link>

                                            <a href="#" @click.prevent="deleteClient(client.id)">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
