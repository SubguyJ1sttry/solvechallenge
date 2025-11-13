package com.tf4beta.ems.main.repository;

import org.springframework.data.jpa.repository.JpaRepository;
import com.tf4beta.ems.main.entity.User;
import org.springframework.stereotype.Repository;

@Repository
public interface UserRepository extends JpaRepository<User, Long> {
    User findByUsername(String username);
}
