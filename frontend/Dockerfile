# Use official Node.js image as a parent image
FROM node:16

# Set working directory inside the container
WORKDIR /app

# Copy the package.json and install dependencies
COPY package*.json ./
RUN npm install

# Copy the rest of the application code to the working directory
COPY . .

# Build the Vue.js app for production
RUN npm run build

# Expose port 3000 for development (if using npm run serve) or the build if served via a server
EXPOSE 3000

# Default command to run the frontend app
CMD ["npm", "run", "serve"]
