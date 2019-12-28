import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MessageManagementComponent } from './message-management.component';

describe('MessageManagementComponent', () => {
  let component: MessageManagementComponent;
  let fixture: ComponentFixture<MessageManagementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MessageManagementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MessageManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
