<template>
    <div class="my-2 shadow  text-white bg-dark p-1" id="">
        <div class="d-flex justify-content-between" v-for="(item, i) in pagination" :key="i">
            <table class="ms-1">
                <tr>
                    <td class="align-middle">{{ item.name }}</td>
                    <td class="align-middle"> -</td>
                    <td class="align-middle">{{ item.email }}</td>
                    <td class="align-middle"></td>
                </tr>
            </table>
            <div>
                <button id="create_request_btn_" @click.prevent="sendConnectRequest(item.id)" class="btn btn-primary me-1 my-1">Connect</button>
            </div>
        </div>
        <div class="d-flex align-items-center mb-2 text-white bg-dark p-1 shadow" v-if="loader" style="height: 45px">
            <strong class="ms-1 text-primary">Loading...</strong>
            <div class="spinner-border ms-auto text-primary me-4" role="status" aria-hidden="true"></div>
        </div>
        <div class="d-flex justify-content-center" v-if="pagination.length < totalSuggestions">
            <button @click="loadMore" class="btn btn-primary me-1 my-1">Load More</button>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import {toast} from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const suggestions = ref([]);
const currentPage = ref(1);
const itemsPerPage = 10;
const totalSuggestions = ref(0);
const loader = ref(false);

function getSuggestions() {
    loader.value = true;
    axios
        .get(`get-suggestions?page=${currentPage.value}&perPage=${itemsPerPage}`)
        .then((res) => {
            suggestions.value = [...suggestions.value, ...res.data.suggestions];
            totalSuggestions.value = res.data.suggestions.length;
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
        loader.value = false;
    });
}

function sendConnectRequest(id) {
    axios
        .post(`send-connection`, { id: id })
        .then((res) => {
            console.log(res.data);
            toast.success(res.data.message);
        })
        .catch((error) => {
            console.error(error);
        });
}


function loadMore() {
    currentPage.value += 1;
    getSuggestions();
}

onMounted(() => {
    getSuggestions();
});

const pagination = ref([]);


watch(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    pagination.value = suggestions.value.slice(0, end);
});
</script>

