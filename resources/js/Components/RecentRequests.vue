<template>
    <div class="my-2 shadow text-white bg-dark p-1" id="">
        <div class="d-flex justify-content-between" v-for="(item, i) in pagination" :key="i">
            <table class="ms-1">
                <td class="align-middle">{{ item.receiver.name }}</td>
                <td class="align-middle"> - </td>
                <td class="align-middle">{{ item.receiver.email }}</td>
                <td class="align-middle"></td>
            </table>
            <div>
                <button id="accept_request_btn_" @click.prevent="acceptRequest(item.id)"  class="btn btn-primary me-1 my-1"
                        onclick="">Accept
                </button>
            </div>
        </div>
        <div class="d-flex justify-content-center" v-if="pagination.length < totalSuggestions">
            <button @click="loadMore" class="btn btn-primary me-1 my-1">Load More</button>
        </div>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from "vue";
import {toast} from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const recentRequests = ref([]);
const currentPage = ref(1);
const itemsPerPage = 10;
const totalSuggestions = ref(0);
const loader = ref(false);

function getRecentRequests() {
    loader.value = true;
    axios
        .get(`get-recent-requests?page=${currentPage.value}&perPage=${itemsPerPage}`)
        .then((res) => {
            recentRequests.value = [...recentRequests.value, ...res.data.suggestions];
            totalSuggestions.value = res.data.suggestions.length;
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            loader.value = false;
        });
}

function acceptRequest(id) {
    axios
        .post(`accept-connection-request`, { id: id })
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
    getRecentRequests
}

onMounted(() => {
    getRecentRequests();
});

const pagination = ref([]);

watch(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    pagination.value = recentRequests.value.slice(0, end);
});
</script>
