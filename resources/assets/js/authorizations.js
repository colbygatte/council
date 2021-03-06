let user = window.App.user;

let authorizations = {
    updateReply(reply) {
        return reply.user_id === user.id;
    },

    updateThread(thread) {
        return thread.user_id === user.id;
    },

    owns(model, prop = 'user_id') {
        return model[prop] === user.id;
    },

    isAdmin() {
        return ['JohnDoe', 'JaneDoe'].includes(user.name);
    }
};

module.exports = authorizations;

