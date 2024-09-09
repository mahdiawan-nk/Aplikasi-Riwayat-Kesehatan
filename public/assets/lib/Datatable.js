class DataTable {
    constructor(options) {
        this.elmtTable = $(options.tableSelector);
        this.paginationList = $(options.paginationSelector);
        this.showingInfo = $(options.showingInfoSelector);
        this.baseUrlApi = options.baseUrlApi;
        this.token = options.token;
        this.columns = options.columns; // Array of objects with key and label for dynamic column rendering
        this.limit = options.limit || 10;
        this.currentPage = 1;
        this.lastPage = 1;
        this.searchTerm = "";
        this.useAxios =
            options.useAxios !== undefined ? options.useAxios : true; // Default to using axios

        // Initial load
        this.fetchListData();
    }

    async fetchListData(page = this.currentPage) {
        if (page < 1) return;

        this.currentPage = page;
        this.elmtTable.html(`<tr>
            <td colspan="${this.columns.length + 1}" class="text-center">
                <i class="fa-solid fa-spinner fa-spin-pulse fa-xl m-5" style="color: #63E6BE;font-size:5em"></i>
            </td>
        </tr>`);

        try {
            const response = await this.getData();
            const data = this.useAxios ? response.data : response;
            this.renderData(data);
            this.updatePaginationInfo(data);
            this.renderPaginationControls();
        } catch (error) {
            console.error(error);
        }
    }

    async getData() {
        if (this.useAxios) {
            return axios.get(this.baseUrlApi, {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${this.token}`,
                    "Content-Type": "application/json",
                },
                params: {
                    limit: this.limit,
                    page: this.currentPage,
                    search: this.searchTerm,
                },
            });
        } else {
            return $.ajax({
                url: this.baseUrlApi,
                type: "GET",
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${this.token}`,
                    "Content-Type": "application/json",
                },
                data: {
                    limit: this.limit,
                    page: this.currentPage,
                    search: this.searchTerm,
                },
            });
        }
    }

    renderData(data) {
        const listData = data.data.data;
        if (listData.length < 1) {
            this.elmtTable.html(`<tr>
                <td colspan="${this.columns.length}" class="text-center">
                    <span class="my-2 fs-4">Tidak Ada Data Ditemukan</span>
                </td>
            </tr>`);
            return;
        }

        const viewTable = listData
            .map((item) => {
                let row = `<tr>`;
                this.columns.forEach((column) => {
                    const value = item[column.key] || "";
                    row += `<td>${
                        column.render ? column.render(value, item) : value
                    }</td>`;
                });
                row += `
                
            </tr>`;
                return row;
            })
            .join("");

        this.elmtTable.html(viewTable);
    }

    updatePaginationInfo(data) {
        const paginate = data.data.pagination;
        this.currentPage = paginate.current_page;
        this.lastPage = paginate.last_page;
        this.showingInfo.html(
            `<b>${paginate.from}</b> to <b>${paginate.to}</b> of <b>${paginate.total}</b> entries`
        );
    }

    renderPaginationControls() {
        this.paginationList.empty();

        // First and previous buttons
        this.paginationList.append(`
            <li class="page-item ${this.currentPage === 1 ? "disabled" : ""}">
                <a class="page-link" style="cursor:pointer" ${
                    this.currentPage === 1
                        ? ""
                        : 'onclick="dataTable.fetchListData(1)"'
                }>First</a>
            </li>
            <li class="page-item ${this.currentPage === 1 ? "disabled" : ""}">
                <a class="page-link" style="cursor:pointer" ${
                    this.currentPage === 1
                        ? ""
                        : `onclick="dataTable.fetchListData(${
                              this.currentPage - 1
                          })"`
                }><i class="fa-solid fa-angles-left"></i></a>
            </li>
        `);

        // Page numbers
        const maxVisiblePages = 3;
        let startPage = this.currentPage - Math.floor(maxVisiblePages / 2);
        let endPage = this.currentPage + Math.floor(maxVisiblePages / 2);

        if (startPage < 1) {
            startPage = 1;
            endPage = maxVisiblePages;
        }

        if (endPage > this.lastPage) {
            endPage = this.lastPage;
            startPage = this.lastPage - maxVisiblePages + 1;
            if (startPage < 1) {
                startPage = 1;
            }
        }

        // Display first page and ellipsis if necessary
        if (startPage > 1) {
            this.paginationList.append(`
                <li class="page-item">
                    <a class="page-link" style="cursor:pointer" onclick="dataTable.fetchListData(1)">1</a>
                </li>
            `);
            if (startPage > 2) {
                this.paginationList.append(
                    '<li class="page-item disabled"><a class="page-link">...</a></li>'
                );
            }
        }

        // Page number buttons
        for (let i = startPage; i <= endPage; i++) {
            this.paginationList.append(`
                <li class="page-item ${this.currentPage === i ? "active" : ""}">
                    <a class="page-link" style="cursor:pointer" onclick="dataTable.fetchListData(${i})">${i}</a>
                </li>
            `);
        }

        // Last page and ellipsis if necessary
        if (endPage < this.lastPage) {
            if (endPage < this.lastPage - 1) {
                this.paginationList.append(
                    '<li class="page-item disabled"><a class="page-link">...</a></li>'
                );
            }
            this.paginationList.append(`
                <li class="page-item">
                    <a class="page-link" style="cursor:pointer" onclick="dataTable.fetchListData(${this.lastPage})">${this.lastPage}</a>
                </li>
            `);
        }

        // Next and last buttons
        this.paginationList.append(`
            <li class="page-item ${
                this.currentPage === this.lastPage ? "disabled" : ""
            }">
                <a class="page-link" style="cursor:pointer" ${
                    this.currentPage === this.lastPage
                        ? ""
                        : `onclick="dataTable.fetchListData(${
                              this.currentPage + 1
                          })"`
                }>
                    <i class="fa-solid fa-angles-right"></i>
                </a>
            </li>
            <li class="page-item ${
                this.currentPage === this.lastPage ? "disabled" : ""
            }">
                <a class="page-link" style="cursor:pointer" ${
                    this.currentPage === this.lastPage
                        ? ""
                        : `onclick="dataTable.fetchListData(${this.lastPage})"`
                }>Last</a>
            </li>
        `);
    }

    setSearchTerm(value) {
        this.searchTerm = value;
        this.fetchListData();
    }

    setLimit(value) {
        this.limit = value;
        this.fetchListData();
    }

    refreshData() {
        this.fetchListData();
    }

    useAxiosMethod() {
        this.useAxios = true;
        this.fetchListData();
    }

    useJQueryAjaxMethod() {
        this.useAxios = false;
        this.fetchListData();
    }
}
