function get_request(url, callback) {

    let req = new Request(url, {
        method: "GET",
    });

    fetch(req)
        .then(response => {
            return response.json();
        })
        .then(data => {
            //console.log(data);
            callback.call(this, [data]);
        })
        .catch(err => {
            console.log(err);
        });

}
