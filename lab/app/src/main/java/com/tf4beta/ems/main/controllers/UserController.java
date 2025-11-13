package com.tf4beta.ems.main.controllers;

import com.tf4beta.ems.main.entity.User;
import com.tf4beta.ems.main.service.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class UserController {

    @Autowired
    private UserService userService;

    @GetMapping("/register")
    public String showRegistrationForm(Model model) {
        model.addAttribute("user", new User());
        return "register";
    }

    @PostMapping("/register")
    public String registerUserAccount(@ModelAttribute("user") User user, Model model) {
        if (userService.findByUsername(user.getUsername()) != null) {
            model.addAttribute("error", "Username already exists");
            return "register";
        }

        // Thiết lập mặc định role là "User" nếu không cung cấp hoặc nếu không phải là "Administrator"
        if (user.getRole() == null || !"ROLE_ADMINISTRATOR".equals(user.getRole())) {
            user.setRole("ROLE_USER");
        }

        userService.save(user);
        return "redirect:/login";
    }
}
