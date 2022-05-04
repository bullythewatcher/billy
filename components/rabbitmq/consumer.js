const amqp = require('amqplib/callback_api');
const axios = require('axios').default;
const config = require("./config.json");

function sendMessage (data) {

    const response = axios({
        url: config.chatbot_url,
        method: 'POST',
        timeout: 60000,
        data: data,
        responseType: 'json',
        json: true
    })

}

const options = {
    clientProperties:
        {
            connection_name: 'producer-service'
        }
};

amqp.connect(config.rabbitmq_url, options, (error, connection) => {
    if (error) {
        throw err;
    }

    connection.createChannel((connErr, channel) => {
        if (connErr) {
            throw connErr;
        }

        channel.assertQueue('test_queue', { durable: true });

        channel.prefetch(1);

        channel.consume('test_queue', (msg) => {
            console.log(msg.content.toString());
            const message_send = sendMessage(msg.content.toString());
            //console.log('message_send', message_send);
            channel.ack(msg);

            /*
            setTimeout(() => {
                channel.ack(msg);
                connection.close();
                process.exit(0);
            }, 500);
             */

        });
    });
});