window.onload = function() {
    const request = window.superagent;
    request
        .post('main.php')
        .type('form')
        .send({id: "kaseiaoki"})
        .set('Accept', 'application/json')
        .end(function(err, res){

            let data = JSON.parse(res.text);
            var ctx = document.getElementById('myChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: 'contributions',
                        data: Object.values(data),
                        backgroundColor: "rgba(153,255,51,0.4)"
                    }]
                }
            });
        });
};
