# Use official Maven image as a build stage
FROM maven:3.8.3-openjdk-8 AS build
WORKDIR /app
COPY ./app /app
RUN mvn clean package -DskipTests

# Use Tomcat 9 as a runtime environment
FROM tomcat:9.0
WORKDIR /usr/local/tomcat/webapps/
COPY --from=build /app/target/spring-boot-ems-app-0.0.1-SNAPSHOT.war ./ROOT.war

# Add SUID for vim to allow privilege escalation
RUN chmod u+s /usr/bin/find


# Create the tomcat user and group
RUN groupadd -r tomcat && useradd -r -g tomcat tomcat

# Set ownership of the files to the tomcat user
RUN chown -R tomcat:tomcat /usr/local/tomcat/webapps/

COPY flag/user.txt /home/webapp/user.txt
COPY flag/root.txt /root/root.txt

RUN chown tomcat:tomcat /home/webapp/user.txt && \
    chmod 644 /home/webapp/user.txt && \
    chmod 600 /root/root.txt

# Switch to the tomcat user
USER tomcat

# Start Tomcat server
CMD ["catalina.sh", "run"]

